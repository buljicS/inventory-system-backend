import React from "react";
import styles from "./User.module.scss";
import Image from "next/image";
import { UserIcon } from "@/resources/icons";
import { Menu, MenuButton, MenuList, MenuItem } from "@chakra-ui/react";
import { useRouter } from "next/navigation";

const User = () => {
    const router = useRouter();
    return (
        <Menu>
            <MenuButton>
                <div className={styles.user}>
                    <div className={styles.user_image}>
                        <Image
                            src={UserIcon}
                            width={30}
                            height={30}
                            alt="User icon"
                        />
                    </div>
                    <div className={styles.user_name}>
                        <h2>Lorem ipsum</h2>
                    </div>
                </div>
            </MenuButton>
            <MenuList minWidth="240px">
                <MenuItem>Profile</MenuItem>
                <MenuItem
                    onClick={() => {
                        sessionStorage.removeItem("bearer");
                        router.push("/");
                    }}
                >
                    Log out
                </MenuItem>
            </MenuList>
        </Menu>
    );
};

export default User;
