import styles from "./Date.module.scss";
import { getCurrentDateTime } from "@/utils/functions";
import { useState, useEffect } from "react";

const Date = () => {
    const [dateTime, setDateTime] = useState(getCurrentDateTime());

    useEffect(() => {
        const intervalId = setInterval(() => {
            setDateTime(getCurrentDateTime());
        }, 1000);

        return () => clearInterval(intervalId);
    }, []);

    return (
        <div className={styles.date}>
            <div className={styles.date_top}>{dateTime}</div>
            <div className={styles.date_bottom}>PROGRAMATORI v1.0</div>
        </div>
    );
};

export default Date;
