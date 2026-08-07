# Fr3nch QR Tracker

[qr.fr3nch.com](https://qr.fr3nch.com) is a managed QR-code platform for creating, organizing, and measuring QR destinations. It gives teams a durable way to distribute a QR code while retaining control of where it resolves and insight into how it is used.

## What It Does

- Creates QR codes that forward visitors to configurable destinations.
- Supports branded QR-code presentation, including colors and associated images.
- Organizes codes with tags and sources for search, reporting, and administration.
- Records scan activity so code owners can understand engagement over time.
- Provides authenticated management workflows while keeping intended QR destinations available to the public.

## Platform Overview

The service is built with CakePHP and PHP, backed by a relational database, and delivered as a containerized web application. It is designed to run as a small, independently deployable service with a reverse proxy handling HTTPS, certificate management, and secure public access.

Production releases are built and validated through continuous integration before being published as immutable container images. Deployments preserve the application data needed for uploads, generated QR assets, sessions, and operational logs across container replacement.

## Reliability and Security

The application combines authentication and authorization controls with automated database migrations, repeatable release builds, code-quality checks, static analysis, and automated tests. Production traffic is protected with HTTPS, HSTS, and automatically renewed TLS certificates.

## License

This project is available under the [MIT License](LICENSE).
