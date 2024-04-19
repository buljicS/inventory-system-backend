import Image from "next/image";
import styles from "./ProfileSideBar.module.scss";
import { UserIcon } from "@/resources/icons";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { useMemo } from "react";

const ProfileSideBar = () => {
    const userInformation = JSON.parse(sessionStorage.getItem("user")!);
    const link = usePathname();

    const isActive = useMemo(() => {
        return link === "/dashboard/profile";
    }, [link]);

    return (
        <Link href="/dashboard/profile">
            <div className={styles.profile}>
                <div className={styles.profile_image}>
                    <Image
                        src={UserIcon}
                        width={50}
                        height={50}
                        alt="User image"
                    />
                </div>
                <div className={styles.profile_info}>
                    <span
                        className={`${styles.profile_info_name} ${
                            isActive ? styles.profile_info_name_active : ""
                        }`}
                    >
                        Lorem Ipsum
                    </span>
                    <span className={styles.profile_info_mail}>
                        {userInformation.user}
                    </span>
                </div>
            </div>
        </Link>
    );
};

export default ProfileSideBar;
