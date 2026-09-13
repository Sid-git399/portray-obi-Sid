# Architecture

Portail OBI follows a lightweight MVC architecture.

The application separates configuration, controllers, models, services, views, middleware and infrastructure.

The main report flow is:

User -> Catalogue -> RapportController -> ObiPublisherClient -> Oracle BI Publisher.
