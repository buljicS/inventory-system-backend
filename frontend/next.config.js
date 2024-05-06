/** @type {import('next').NextConfig} */
const nextConfig = {
    reactStrictMode: false,
    env: {
        BASE_URL: process.env.BASE_URL,
      },
    images: {
        remotePatterns: [
          {
            protocol: "https",
            hostname: "storage.googleapis.com",
            port: ""
          }
        ],
      },
}

module.exports = nextConfig
