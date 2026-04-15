# Nuxt Niw E-commerce

An simple e-commerce application built with **Nuxt 3** and **WinterCMS**.

## 🚀 Technology Stack

### Frontend
- **Framework**: [Nuxt 3](https://nuxt.com/) (Vue.js)
- **State Management**: [Pinia](https://pinia.vuejs.org/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Validation**: [Zod](https://zod.dev/)
- **Deployment**: Google Cloud Run

### Backend
- **Framework**: PHP / Laravel (WinterCMS)
- **Architecture**: **Polymorphic** product models
- **Authentication**: **JWT** (JSON Web Token) with custom middleware
- **Deployment**: Google Cloud Run (Apache)

## 🌐 Live URLs

- **Frontend**: [https://nuxt-niw-frontend-629813211350.europe-west1.run.app/](https://nuxt-niw-frontend-629813211350.europe-west1.run.app/)
- **Backend**: [https://nuxt-niw-backend-629813211350.europe-west1.run.app/](https://nuxt-niw-backend-629813211350.europe-west1.run.app/)

---

## 🛠️ Development

This project is fully dockerized. To run locally:

```bash
docker compose up -d
```

Frontend will be available at `http://localhost:3000` and Backend at `http://localhost:8080`.
