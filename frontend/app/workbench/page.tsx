"use client";
import styles from "@/styles/Workbench.module.scss";
import { userAtom } from "@/utils/atoms";
import { useRecoilState } from "recoil";
import { useEffect } from "react";
import { useToast } from "@chakra-ui/react";
import {
    WorkbenchCardModal,
    Logout,
    Date,
    WorkbenchSlider,
} from "@/components";
import { WORKBENCH_CARDS } from "@/utils/constants";

const page = () => {
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
        <div className={styles.workbench}>
            <div className={styles.workbench_layout}>
                <div className={styles.workbench_layout_cards}>
                    {WORKBENCH_CARDS.map((item) => (
                        <WorkbenchCardModal
                            icon={item.icon}
                            title={item.title}
                            description={item.description}
                            type={item.type}
                            key={item.id}
                        />
                    ))}
                </div>
                <div className={styles.workbench_layout_content}>
                    <WorkbenchSlider />
                </div>
            </div>

            <div className={styles.workbench_top}>
                <div className={styles.workbench_top_logout}>
                    <Logout />
                </div>
                <div className={styles.workbench_top_date}>
                    <Date />
                </div>
            </div>
        </div>
    );
};

export default page;
