import styles from "./ProfileInformation.module.scss";
import ProfileForm from "../ProfileForm/ProfileForm";

const ProfileInformation = () => {
    return (
        <div className={styles.information}>
            <div className={styles.information_header}>
                <h3>Information</h3>
            </div>
            <div className={styles.information_form}>
                <ProfileForm />
            </div>
        </div>
    );
};

export default ProfileInformation;
