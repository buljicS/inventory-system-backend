import Link from "next/link";
import styles from "./SideBar.module.scss";
import Image from "next/image";
import { usePathname } from "next/navigation";
import { useMemo, useState } from "react";

import { ArrowIcon } from "@/resources/icons";

const SideBarNavigationItem = ({ route }) => {
    const [showDropDown, setShowDropDown] = useState<boolean>(false);
    const link = usePathname();

    const isActive = useMemo(() => {
        return link === route.link;
    }, [link, route.link]);

    return (
        <Link href={route?.link}>
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

                <span>{route.label}</span>
            </div>
        </Link>
    );
};

export default SideBarNavigationItem;
