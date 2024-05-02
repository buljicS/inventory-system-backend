"use client";
import { SideBar } from "@/components";
import styles from "@/styles/DashboardLayout.module.scss";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { PrimeReactProvider } from "primereact/api";
import { useRecoilState } from "recoil";
import { userAtom } from "@/utils/atoms";

export default function DashboardLayout({ children }) {
    const router = useRouter();
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useRecoilState(userAtom);

    useEffect(() => {
        if (!sessionStorage.getItem("user")) {
            router.push("/");
        } else {
            const user = JSON.parse(sessionStorage.getItem("user")!);

            setUser((prev) => ({
                ...prev,
                userId: user.userId,
                fullName: user.fullName,
                picture: user.picture,
                role: user.role,
                token: user.token,
                email: user.email,
            }));
            setIsLoggedIn(true);
        }
    }, []);

    return (
        isLoggedIn && (
            <PrimeReactProvider>
                <div className={styles.dashboard_container}>
                    <SideBar />
                    <div className={styles.dashboard_main}>{children}</div>
                </div>
            </PrimeReactProvider>
        )
    );
}
