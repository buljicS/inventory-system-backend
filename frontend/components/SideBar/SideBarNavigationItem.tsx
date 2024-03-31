import Link from "next/link";
import styles from "./SideBar.module.scss";
import Image from "next/image";
import { usePathname } from "next/navigation";
import { useMemo } from "react";

const SideBarNavigationItem = ({ route }) => {
    const link = usePathname();

    const isActive = useMemo(() => {
        return link === route.link;
    }, [link, route.link]);

    return (
        <div
            className={`${styles.sidebar_navigation_item} ${
                isActive ? styles.sidebar_active : ""
            }`}
        >
            <div>
                <Image
                    src={route.icon}
                    alt="Navigation icon"
                    width={30}
                    height={30}
                    loading="lazy"
                />
            </div>

            <div>
                <Link href={route.link}>{route.label}</Link>
            </div>
        </div>
    );
};

export default SideBarNavigationItem;
