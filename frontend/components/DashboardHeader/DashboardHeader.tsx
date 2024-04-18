"use client";
import styles from "./DashboardHeader.module.scss";

const DashboardHeader = ({ title }) => {
    return (
        <div className={styles.header}>
            <h1>{title}</h1>
        </div>
    );
};

export default DashboardHeader;
