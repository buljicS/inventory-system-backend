import styles from "./UserNavigation.module.scss";
import Image from "next/image";
import { UserIcon, ArrowIcon } from "@/resources/icons";
import { useRecoilState } from "recoil";
import { userAtom } from "@/utils/atoms";
import { Logout } from "@/components";
import { Menu, MenuButton, MenuList, MenuItem } from "@chakra-ui/react";
import Link from "next/link";

const UserNavigation = () => {
    const [user, setUser] = useRecoilState(userAtom);

    return (
        <Menu placement="top">
            <MenuButton>
                <div className={styles.user}>
                    <div className={styles.user_info}>
                        <h1>{user.fullName}</h1>
                        <h2>{user.role}</h2>
                    </div>
                    <div className={styles.user_image}>
                        <Image
                            src={user.picture ?? UserIcon}
                            width={40}
                            height={40}
                            alt="User icon"
                        />
                    </div>
                    <div className={styles.user_arrow}>
                        <Image
                            src={ArrowIcon}
                            width={20}
                            height={20}
                            alt="Arrow down"
                        />
                    </div>
                </div>
            </MenuButton>
            <MenuList>
                <Link href="/dashboard/profile">
                    <MenuItem>
                        <Image
                            src={UserIcon}
                            alt="User icon"
                            width={20}
                            height={20}
                        />
                        My Profile
                    </MenuItem>
                </Link>
                <MenuItem>
                    <Logout />
                </MenuItem>
            </MenuList>
        </Menu>
    );
};

export default UserNavigation;
