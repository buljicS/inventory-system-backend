"use client";
import { SideBar } from "@/components";
import styles from "@/styles/DashboardLayout.module.scss";

export default function DashboardLayout({ children }) {
    return (
        <div className={styles.dashboard_container}>
            <SideBar />
            {children}
        </div>
    );
}
