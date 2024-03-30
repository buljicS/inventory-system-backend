"use client";
import { SideBar } from "@/components";
import styles from "@/styles/DashboardLayout.module.scss";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";

export default function DashboardLayout({ children }) {
    const router = useRouter();
    const [isLoggedIn, setIsLoggedIn] = useState(false);

    useEffect(() => {
        if (!sessionStorage.getItem("bearer")) {
            router.push("/");
        } else {
            setIsLoggedIn(true);
        }
    }, []);

    return (
        isLoggedIn && (
            <div className={styles.dashboard_container}>
                <SideBar />
                {children}
            </div>
        )
    );
}
