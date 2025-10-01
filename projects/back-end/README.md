# JouwQR Codes Backend

Quick and dirty Kubernetes deployment for the JouwQR Codes Laravel backend.

## Prerequisites

- Kubernetes cluster (DigitalOcean)
- kubectl configured
- Helm 3 installed
- Docker image built and pushed to ghcr.io

## Quick Start

### 1. Build and Push Docker Image

```bash
# Build the image
docker build -t ghcr.io/lodybo/jouwqrcodes-backend:latest .

# Login to GitHub Container Registry
echo $GITHUB_TOKEN | docker login ghcr.io -u USERNAME --password-stdin

# Push the image
docker push ghcr.io/lodybo/jouwqrcodes-backend:latest
```

### 2. Deploy to Kubernetes

```bash
# Install the chart
helm install jouwqrcodes-backend ./helm \
  --namespace jouwqrcodes \
  --create-namespace \
  --set chart.version=0.1.0 \
  --set postgres.password=yourpassword \
  --set environment=production \
  --set secrets.appKey=base64:yourbase64key 

# Or upgrade if already installed
helm upgrade jouwqrcodes-backend ./helm \
  --namespace jouwqrcodes \
  --set chart.version=0.1.0 \
  --set postgres.password=yourpassword \
  --set environment=production \
  --set secrets.appKey=base64:yourbase64key
```

### 4. Check Status

```bash
# Check pods
kubectl get pods -n jouwqrcodes

# Check services
kubectl get svc -n jouwqrcodes

# Check ingress
kubectl get ingress -n jouwqrcodes

# View logs
kubectl logs -n jouwqrcodes -l app=jouwqrcodes-backend -f
```

## Configuration

### Environment Variables

The deployment automatically configures:
- Database connection to PostgreSQL pod
- Laravel environment based on `environment` value
- APP_KEY from secrets
- Session and cache drivers set to database

### Ingress Configuration

Two ingress resources are created:
1. **Main ingress** - Public access with HTTPS redirect
2. **Restricted ingress** - IP whitelisted paths (`/admin`, `/api`)

### Storage

PostgreSQL uses a StatefulSet with persistent volume (5Gi by default via volumeClaimTemplates).

## Troubleshooting

### Pod not starting
```bash
kubectl describe pod -n jouwqrcodes -l app=jouwqrcodes-backend
kubectl logs -n jouwqrcodes -l app=jouwqrcodes-backend
```

### Database connection issues
```bash
kubectl exec -it -n jouwqrcodes jouwqrcodes-backend-postgres-0 -- psql -U jouwqrcodes -d jouwqrcodes
```

### Migrations not running
```bash
kubectl exec -it -n jouwqrcodes deployment/jouwqrcodes-backend -- php artisan migrate:status
```

## Uninstall

```bash
helm uninstall jouwqrcodes-backend --namespace jouwqrcodes
kubectl delete namespace jouwqrcodes
```
