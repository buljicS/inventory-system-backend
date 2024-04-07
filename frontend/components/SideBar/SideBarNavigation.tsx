import SideBarNavigationItem from "./SideBarNavigationItem";
import { SIDEBAR_LINKS } from "@/utils/constants";
import styles from "./SideBar.module.scss";

const SideBarNavigation = () => {
    return (
        <div className={styles.sidebar_navigation}>
            <h5 className={styles.sidebar_navigation_header}>MENU</h5>
            <ul className={styles.sidebar_navigation_list}>
                {SIDEBAR_LINKS.map((item) => (
                    <SideBarNavigationItem key={item.id} route={item} />
                ))}
            </ul>
        </div>
    );
};

export default SideBarNavigation;
