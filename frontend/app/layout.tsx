"use client";
import { Inter } from "next/font/google";
import "@/styles/globals.scss";
import "bootstrap/dist/css/bootstrap.min.css";
import { RecoilRoot } from "recoil";
import { ChakraProvider } from "@chakra-ui/react";

const inter = Inter({ subsets: ["latin"] });

export default function RootLayout({ children }) {
    return (
        <html lang="en">
            <body className={`${inter.className} layout`}>
                <main>
                    <RecoilRoot>
                        <ChakraProvider>{children}</ChakraProvider>
                    </RecoilRoot>
                </main>
            </body>
        </html>
    );
}
