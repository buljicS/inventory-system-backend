"use client";
import React from "react";
import styles from "./SideBar.module.scss";
import Image from "next/image";
import { Logo } from "@/resources/images";
import { useState, useEffect } from "react";
import { Turn as Hamburger } from "hamburger-react";
import { SideBarNavigation, Date } from "@/components";
import { handleBodyScroll } from "@/utils/functions";

const SideBar = () => {
    const [showSideBar, setShowSideBar] = useState(false);

    const toggleSideBar = () => {
        setShowSideBar(!showSideBar);
    };

    useEffect(() => {
        handleBodyScroll(showSideBar);

        return () => {
            handleBodyScroll(false);
        };
    }, [showSideBar]);

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
                    <div className={styles.sidebar_top_logo}>
                        <Image src={Logo} width={150} height={150} alt="Logo" />
                    </div>

                    <div className={styles.sidebar_top_links}>
                        <SideBarNavigation setShowSideBar={setShowSideBar} />
                    </div>
                </div>

                <div className={styles.sidebar_bottom}>
                    <Date />
                </div>
            </div>
        </>
    );
};

export default SideBar;
