FROM node:20-alpine

# Set working directory
WORKDIR /app

# Install dependencies
RUN apk add --no-cache git

# Expose port 3000
EXPOSE 3000

# Start Nuxt in development mode
CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0"]
