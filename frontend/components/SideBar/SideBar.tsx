"use client";
import React from "react";
import styles from "./SideBar.module.scss";
import Image from "next/image";
import { Logo } from "@/resources/images";
import { useState } from "react";
import { Turn as Hamburger } from "hamburger-react";

const SideBar = () => {
    const [showSideBar, setShowSideBar] = useState(false);

    const toggleSideBar = () => {
        setShowSideBar(!showSideBar);
    };

    return (
        <>
            <div
                className={
                    styles.sidebar +
                    (showSideBar ? ` ${styles.sidebar_show}` : "")
                }
            >
                <div className={styles.sidebar_toggle} onClick={toggleSideBar}>
                    <Hamburger
                        toggled={showSideBar}
                        toggle={toggleSideBar}
                        color="#FFF"
                        size={25}
                    />
                </div>
                <div className={styles.sidebar_logo}>
                    <Image src={Logo} alt="logo" height={150} width={150} />
                </div>
            </div>
        </>
    );
};

export default SideBar;
