import SideBarNavigationItem from "./SideBarNavigationItem";
import {
    SIDEBAR_LINKS_EMPLOYER,
    SIDEBAR_LINKS_WORKER,
} from "@/utils/constants";
import styles from "./SideBar.module.scss";
import { useRecoilState } from "recoil";
import { userAtom } from "@/utils/atoms";

const SideBarNavigation = () => {
    const [user, setUser] = useRecoilState(userAtom);

    return (
        <div className={styles.sidebar_navigation}>
            <ul className={styles.sidebar_navigation_list}>
                {user.role === "employer" &&
                    SIDEBAR_LINKS_EMPLOYER.map((item) => (
                        <SideBarNavigationItem key={item.id} route={item} />
                    ))}
                {user.role === "worker" &&
                    SIDEBAR_LINKS_WORKER.map((item) => (
                        <SideBarNavigationItem key={item.id} route={item} />
                    ))}
            </ul>
        </div>
    );
};

export default SideBarNavigation;
