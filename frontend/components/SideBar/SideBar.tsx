"use client";
import React from "react";
import styles from "./SideBar.module.scss";
import Image from "next/image";
import { Logo } from "@/resources/images";
import { useState } from "react";
import { Turn as Hamburger } from "hamburger-react";
import { Logout, ProfileSideBar, SideBarNavigation } from "@/components";

const SideBar = () => {
    const [showSideBar, setShowSideBar] = useState(false);

    const toggleSideBar = () => {
        setShowSideBar(!showSideBar);
    };

    return (
        <>
            <div
                className={`${styles.sidebar} ${
                    showSideBar ? styles.sidebar_show : ""
                }`}
            >
                <div
                    className={`${styles.sidebar_toggle} ${
                        showSideBar ? styles.sidebar_toggle_show : ""
                    }`}
                    onClick={toggleSideBar}
                >
                    <Hamburger
                        toggled={showSideBar}
                        toggle={toggleSideBar}
                        color="#FFF"
                        size={25}
                    />
                </div>

                <div className={styles.sidebar_top}>
                    <div className={styles.sidebar_top_profile}>
                        <ProfileSideBar />
                    </div>

                    <div className={styles.sidebar_top_links}>
                        <SideBarNavigation />
                    </div>
                </div>

                <div className={styles.sidebar_bottom}>
                    <div className={styles.sidebar_bottom_user}>
                        <Logout />
                    </div>
                </div>
            </div>
        </>
    );
};

export default SideBar;
