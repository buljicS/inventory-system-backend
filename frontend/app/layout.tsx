"use client";
import { Inter } from "next/font/google";
import "@/styles/globals.scss";
import "bootstrap/dist/css/bootstrap.min.css";
import { Navigation, Footer } from "@/components";

const inter = Inter({ subsets: ["latin"] });

export default function RootLayout({ children }) {
    return (
        <html lang="en">
            <body className={inter.className}>
                <Navigation />
                <main>{children}</main>
                <Footer />
            </body>
        </html>
    );
}
