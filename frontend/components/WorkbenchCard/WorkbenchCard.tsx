import styles from "./WorkbenchCard.module.scss";
import Image from "next/image";

const WorkbenchCard = ({ icon, title, body }) => {
    return (
        <div className={styles.workbench_card}>
            <div className={styles.workbench_card_header}>
                <div className={styles.workbench_card_header_icon}>
                    <Image src={icon} width={40} height={40} alt="Card icon" />
                </div>
                <div className={styles.workbench_card_header_title}>
                    <h1>{title}</h1>
                </div>
            </div>
            <div className={styles.workbench_card_body}>
                <p>{body}</p>
            </div>
        </div>
    );
};

export default WorkbenchCard;
