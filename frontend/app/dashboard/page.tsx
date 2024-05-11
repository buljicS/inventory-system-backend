"use client";
import styles from "@/styles/Dashboard.module.scss";
import { userAtom } from "@/utils/atoms";
import { useRecoilState } from "recoil";
import { useEffect } from "react";
import { useToast } from "@chakra-ui/react";
import { DashboardHeader } from "@/components";
import { useToastMessage } from "@/utils/hooks";

const Dashboard = () => {
    const [user, setUser] = useRecoilState(userAtom);
    const showToast = useToastMessage();

    useEffect(() => {
        if (user.approveLogin) {
            showToast("success", "You have successfuly logged in.");
            setUser((prev) => ({ ...prev, approveLogin: false }));
        }
    }, []);

    return (
        <div className={styles.dashboard_main}>
            <DashboardHeader title="Welcome!" />
        </div>
    );
};

export default Dashboard;
