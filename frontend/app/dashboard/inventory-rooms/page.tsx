"use client";
import { DashboardHeader, RoomTable } from "@/components";
import styles from "@/styles/Rooms.module.scss";

const page = () => {
    return (
        <div>
            <DashboardHeader title="Rooms" />
            <div className={styles.rooms}>
                <RoomTable />
            </div>
        </div>
    );
};

export default page;
