"use client";
import styles from "./DashboardHeader.module.scss";
import { UserNavigation } from "@/components";

const DashboardHeader = ({ title }) => {
    return (
        <div className={styles.header}>
            <div className={styles.header_left}>
                <h1>{title}</h1>
            </div>
            <div className={styles.header_right}>
                <UserNavigation />
            </div>
        </div>
    );
};

export default DashboardHeader;
