import styles from "./ProfileProgress.module.scss";
import { UserWhiteIcon, PersonalData } from "@/resources/icons";
import Image from "next/image";
import { useUserProgress } from "@/utils/hooks";

const ProfileProgress = () => {
    const { progress, userStatus } = useUserProgress();

    return (
        <div className={styles.progress}>
            <div className={styles.progress_top}>
                <div className={styles.progress_top_image}></div>
                <div className={styles.progress_top_number}>
                    <h2>Progress</h2>
                    <span>{progress}%</span>
                </div>
            </div>
            <div className={styles.progress_bottom}>
                <div className={styles.progress_bottom_card}>
                    <Image
                        src={UserWhiteIcon}
                        width={50}
                        height={50}
                        alt="User icon"
                    />
                    <h3>Profile image</h3>
                    {userStatus.image ? (
                        <span className={styles.success}>Done</span>
                    ) : (
                        <span className={styles.todo}>Missing</span>
                    )}
                </div>

                <div className={styles.progress_bottom_card}>
                    <Image
                        src={PersonalData}
                        width={50}
                        height={50}
                        alt="Information icon"
                    />
                    <h3>Profile information</h3>
                    {userStatus.information ? (
                        <span className={styles.success}>Done</span>
                    ) : (
                        <span className={styles.todo}>Missing</span>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ProfileProgress;
