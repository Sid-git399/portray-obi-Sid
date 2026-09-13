<?php

namespace App\Services;

use SoapClient;
use SoapFault;
use Exception;

class ObiPublisherClient
{
    private ?SoapClient $client = null;
    private string $username;
    private string $password;
    private string $mode;
    private string $demoDir;

    public function __construct(array $config)
    {
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->mode     = $config['mode'] ?? 'demo';
        $this->demoDir  = rtrim($config['demo_dir'] ?? __DIR__ . '/../../storage/demo_reports', '/');

        if ($this->mode === 'live') {
            $options = [
                'trace'              => true,
                'exceptions'         => true,
                'connection_timeout' => $config['timeout'] ?? 30,
                'cache_wsdl'         => WSDL_CACHE_NONE,
            ];

            // Si le serveur OBI utilise un certificat HTTPS auto-signe (test/demo uniquement) :
            // $context = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
            // $options['stream_context'] = $context;

            $this->client = new SoapClient($config['wsdl'], $options);
        }
    }

    /**
     * Recupere la liste des parametres attendus par un rapport OBI.
     */
    public function getReportParameters(string $reportAbsolutePath): array
    {
        if ($this->mode !== 'live') {
            return [];
        }

        try {
            $result = $this->client->getReportParameters([
                'reportAbsolutePath' => $reportAbsolutePath,
                'userID'             => $this->username,
                'password'           => $this->password,
            ]);
            return $result->getReportParametersReturn->item ?? [];
        } catch (SoapFault $e) {
            throw new Exception("Erreur OBI (getReportParameters) : " . $e->getMessage());
        }
    }

    /**
     * Execute un rapport OBI et retourne le fichier genere.
     *
     * @param string $reportPath Chemin du rapport dans le catalogue OBI
     * @param string $format     'pdf' | 'rtf' | 'excel2007' | 'csv' | 'html'
     * @param array  $filters    ['nom_parametre' => 'valeur', ...]
     * @return array{content: string, contentType: string}
     */
    public function runReport(string $reportPath, string $format, array $filters = []): array
    {
        if ($this->mode !== 'live') {
            return $this->runReportDemo($reportPath, $format);
        }

        $paramItems = [];
        foreach ($filters as $name => $value) {
            $paramItems[] = [
                'name'                 => $name,
                'values'               => [$value],
                'multiValuesAllowed'   => false,
                'selectAll'            => false,
                'refreshParamOnChange' => false,
                'templateParam'        => false,
                'useNullForAll'        => false,
            ];
        }

        $reportRequest = [
            'attributeFormat'           => $format,
            'attributeLocale'           => 'fr-FR',
            'attributeTemplate'         => '',
            'reportAbsolutePath'        => $reportPath,
            'byPassCache'               => true,
            'flattenXML'                => false,
            'sizeOfDataChunkDownload'   => -1,
            'parameterNameValues'       => [
                'listOfParamNameValues' => ['item' => $paramItems],
            ],
        ];

        try {
            $response = $this->client->runReport([
                'reportRequest' => $reportRequest,
                'userID'        => $this->username,
                'password'      => $this->password,
            ]);

            $result = $response->runReportReturn;

            return [
                'content'     => $result->reportBytes,
                'contentType' => $result->reportContentType,
            ];
        } catch (SoapFault $e) {
            throw new Exception("Erreur OBI (runReport) sur '{$reportPath}' : " . $e->getMessage());
        }
    }

    /**
     * Mode demo : renvoie un fichier pre-genere au lieu d'appeler le vrai SOAP.
     * Sert de filet de securite si le serveur OBI est injoignable (section 12 du guide).
     */
    private function runReportDemo(string $reportPath, string $format): array
    {
        $extension = match ($format) {
            'pdf' => 'pdf',
            'rtf' => 'rtf',
            'excel2007', 'xls' => 'xlsx',
            'csv' => 'csv',
            default => 'pdf',
        };

        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '_', basename($reportPath, '.xdo'));
        $demoFile = $this->demoDir . '/' . $slug . '.' . $extension;

        $contentType = match ($extension) {
            'pdf'  => 'application/pdf',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'rtf'  => 'application/rtf',
            'csv'  => 'text/csv',
            default => 'application/octet-stream',
        };

        if (file_exists($demoFile)) {
            return [
                'content'     => file_get_contents($demoFile),
                'contentType' => $contentType,
            ];
        }

        // Aucun fichier demo pre-genere : on cree un PDF minimal a la volee pour ne pas casser la demo.
        $placeholder = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n"
            . "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n"
            . "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 595 842]/Resources<</Font<</F1 4 0 R>>>>/Contents 5 0 R>>endobj\n"
            . "4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj\n"
            . "5 0 obj<</Length 100>>stream\nBT /F1 14 Tf 50 780 Td (Rapport de demonstration - " . addslashes($slug)
            . ") Tj ET\nendstream endobj\ntrailer<</Root 1 0 R>>\n";

        return [
            'content'     => $placeholder,
            'contentType' => 'application/pdf',
        ];
    }
}
