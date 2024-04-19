import styles from "./ProfilePhoto.module.scss";
import { useState, useRef } from "react";
import Image from "next/image";
import Button from "react-bootstrap/Button";

const ProfilePhotoCard = () => {
    const [file, setFile] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const showImage = (e) => {
        console.log(e.target.files);
        setFile(URL.createObjectURL(e.target.files[0]));
    };

    const uploadImage = () => {
        fileInputRef.current!.click();
    };

    return (
        <div className={styles.profile_photo}>
            <div className={styles.profile_photo_header}>
                <h3>Photo</h3>
            </div>

            <div className={styles.profile_photo_body}>
                <div className={styles.profile_photo_body_left}>
                    {file && (
                        <Image
                            src={file}
                            width={70}
                            height={70}
                            alt="User image"
                        />
                    )}
                </div>

                <div className={styles.profile_photo_body_right}>
                    <div>
                        <h3>Upload your profile image</h3>
                    </div>
                    <div className={styles.profile_photo_body_right_buttons}>
                        <Button
                            onClick={uploadImage}
                            variant="outline-primary"
                            size="sm"
                        >
                            Browse
                        </Button>
                        <input
                            type="file"
                            ref={fileInputRef}
                            onChange={showImage}
                        />
                        <Button variant="outline-success" size="sm">
                            Upload
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProfilePhotoCard;
