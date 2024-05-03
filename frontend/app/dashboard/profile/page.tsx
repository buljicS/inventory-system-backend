"use client";
import styles from "@/styles/Profile.module.scss";
import {
    DashboardHeader,
    ProfileForm,
    ProfilePasswordForm,
    ProfilePhoto,
    ProfileProgress,
} from "@/components";

const page = () => {
    return (
        <div className={styles.profile}>
            <DashboardHeader title="Profile" />
            <div className={styles.profile_body}>
                <div className={styles.profile_body_top}>
                    <ProfilePhoto />
                    <ProfileProgress />
                </div>
                <div className={styles.profile_body_bottom}>
                    <ProfileForm />
                    <ProfilePasswordForm />
                </div>
            </div>
        </div>
    );
};

export default page;
