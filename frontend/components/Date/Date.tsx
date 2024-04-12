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

    return <div className={styles.date}>{dateTime}</div>;
};

export default Date;
