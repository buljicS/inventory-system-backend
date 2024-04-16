"use client";
import { Footer } from "@/components";
import styles from "@/styles/DashboardLayout.module.scss";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";

export default function WorkbenchLayout({ children }) {
    const router = useRouter();
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    useEffect(() => {
        if (!sessionStorage.getItem("user")) {
            router.push("/");
        } else {
            setIsLoggedIn(true);
        }
    }, []);

    return (
        isLoggedIn && (
            <div className={styles.workbench_container}>
                {children}
                <Footer />
            </div>
        )
    );
}
