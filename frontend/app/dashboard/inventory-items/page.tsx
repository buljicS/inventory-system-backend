"use client";
import styles from "@/styles/DashboardLayout.module.scss";
import { DashboardHeader, ItemsTable } from "@/components";

const page = () => {
    return (
        <>
            <DashboardHeader title="Items" />
            <div className={styles.items}>
                <ItemsTable />
            </div>
        </>
    );
};

export default page;
