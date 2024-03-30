import React from "react";
import styles from "./Logout.module.scss";
import Image from "next/image";
import { PowerIcon } from "@/resources/icons";
import { useRouter } from "next/navigation";

const Logout = () => {
    const router = useRouter();
    return (
        <div className={styles.user_logout}>
            <div className={styles.user_logout_icon}>
                <Image
                    src={PowerIcon}
                    alt="Logout icon"
                    width={30}
                    height={30}
                    onClick={() => {
                        sessionStorage.removeItem("bearer");
                        router.push("/");
                    }}
                />
            </div>
        </div>
    );
};

export default Logout;
