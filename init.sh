#!/bin/bash

# Create directory structure
mkdir -p app/{Controllers,Models,Services,Middleware}
mkdir -p config
mkdir -p database
mkdir -p public/{css,js,images,uploads}
mkdir -p resources/{views/{admin,auth,layouts,shop},lang,assets}
mkdir -p routes
mkdir -p storage/{app,logs,cache}
mkdir -p tests

# Set permissions
chmod -R 755 public/uploads
chmod -R 755 storage

# Create basic placeholder files
touch app/Controllers/.gitkeep
touch app/Models/.gitkeep
touch app/Services/.gitkeep
touch app/Middleware/.gitkeep
touch config/.gitkeep
touch database/.gitkeep
touch resources/views/.gitkeep
touch routes/.gitkeep
touch storage/.gitkeep
touch tests/.gitkeep
