import styles from "./ProfileProgress.module.scss";
import { UserIcon, PersonalData } from "@/resources/icons";
import Image from "next/image";

const ProfileProgress = () => {
    return (
        <div className={styles.progress}>
            <div className={styles.progress_top}>
                <div className={styles.progress_top_image}></div>
                <div className={styles.progress_top_number}>
                    <h2>Progress</h2>
                    <span>50%</span>
                </div>
            </div>
            <div className={styles.progress_bottom}>
                <div className={styles.progress_bottom_card}>
                    <Image
                        src={UserIcon}
                        width={50}
                        height={50}
                        alt="User icon"
                    />
                    <h3>Profile image</h3>
                    <span className={styles.success}>Done</span>
                </div>

                <div className={styles.progress_bottom_card}>
                    <Image
                        src={PersonalData}
                        width={50}
                        height={50}
                        alt="User icon"
                    />
                    <h3>Profile information</h3>
                    <span className={styles.todo}>Missing</span>
                </div>
            </div>
        </div>
    );
};

export default ProfileProgress;
