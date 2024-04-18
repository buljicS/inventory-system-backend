"use client";
import styles from "@/styles/Profile.module.scss";
import {
    DashboardHeader,
    ProfilePhoto,
    ProfileInformation,
    ProfileProgress,
} from "@/components";

const page = () => {
    return (
        <div className={styles.profile}>
            <DashboardHeader title="Profile" />
            <div className={styles.profile_body}>
                <div className={styles.profile_body_left}>
                    <ProfilePhoto />
                    <ProfileInformation />
                </div>
                <div className={styles.profile_body_right}>
                    <ProfileProgress />
                </div>
            </div>
        </div>
    );
};

export default page;
