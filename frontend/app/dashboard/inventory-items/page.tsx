"use client";
import styles from "@/styles/Items.module.scss";
import { DashboardHeader } from "@/components";

const page = () => {
    return (
        <div className={styles.items}>
            <DashboardHeader title="Items" />
        </div>
    );
};

export default page;
