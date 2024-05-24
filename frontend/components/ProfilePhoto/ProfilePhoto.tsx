import styles from "./ProfilePhoto.module.scss";
import { UserIcon, PlusIcon } from "@/resources/icons";
import { useRecoilState } from "recoil";
import { userAtom } from "@/utils/atoms";
import Image from "next/image";
import { useState } from "react";
import { Form } from "react-bootstrap";
import { MAX_PROFILE_IMAGE_SIZE, VALID_IMAGE_TYPES } from "@/utils/constants";
import { useToastMessage } from "@/utils/hooks";

const ProfilePhoto = () => {
    const [user, setUser] = useRecoilState(userAtom);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [previewImage, setPreviewImage] = useState<string | null>(null);
    const [error, setError] = useState<boolean>(false);
    const showToast = useToastMessage();

    const handleImageChange = (e) => {
        const file = e.target.files[0];

        if (!VALID_IMAGE_TYPES.includes(file.type)) {
            showToast("error", "Invalid file type. Please upload an image.");
            e.target.value = null;
            setError(true);
            return;
        }

        if (file.size > MAX_PROFILE_IMAGE_SIZE) {
            showToast(
                "error",
                "Image is above 2MB. Please upload a smaller image."
            );
            setError(true);
            e.target.value = null;
            return;
        }

        setError(false);
        setPreviewImage(URL.createObjectURL(e.target.files[0]));
    };

    return (
        <div
            className={`${styles.profile} ${error ? styles.profile_error : ""}`}
        >
            <div className={styles.profile_picture}>
                <Image
                    src={previewImage ?? user.picture ?? UserIcon}
                    width={150}
                    height={150}
                    alt="User picture"
                    className={styles.picture}
                />

                <Form>
                    <div className={styles.profile_picture_add}>
                        <label htmlFor="uploadImage">
                            <Image
                                src={PlusIcon}
                                width={25}
                                height={25}
                                alt="Add picture"
                            />
                        </label>
                        <input
                            type="file"
                            id="uploadImage"
                            hidden
                            onChange={handleImageChange}
                            accept="image/jpeg, image/png"
                        />
                    </div>
                </Form>
            </div>

            <div className={styles.profile_information}>
                <p className={styles.profile_information_name}>
                    {user.fullName}
                </p>
                <p className={styles.profile_information_email}>{user.email}</p>
            </div>
        </div>
    );
};

export default ProfilePhoto;
