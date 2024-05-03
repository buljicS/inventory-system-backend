"use client";
import styles from "@/styles/Dashboard.module.scss";
import { userAtom } from "@/utils/atoms";
import { useRecoilState } from "recoil";
import { useEffect } from "react";
import { useToast } from "@chakra-ui/react";
import { DashboardHeader } from "@/components";

const Dashboard = () => {
    const [user, setUser] = useRecoilState(userAtom);
    const toast = useToast();

    useEffect(() => {
        if (user.approveLogin) {
            toast({
                title: "Status",
                description: "You have successfuly logged in.",
                status: "success",
                duration: 3000,
                isClosable: true,
                position: "top-right",
            });
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
