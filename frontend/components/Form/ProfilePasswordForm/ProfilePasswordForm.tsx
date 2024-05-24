import styles from "./ProfilePasswordForm.module.scss";
import {
    PROFILE_PASSWORD_INPUTS,
    PASSWORD_PROFILE_SCHEMA,
} from "@/utils/constants";
import { Form } from "react-bootstrap";
import { FormInput, FormSubmit } from "@/components";
import { useState } from "react";
import { useForm, SubmitHandler } from "react-hook-form";
import { TPasswordProfileData } from "@/utils/types";
import { zodResolver } from "@hookform/resolvers/zod";
import axiosInstance from "@/utils/axiosInstance";
import { useToastMessage } from "@/utils/hooks";
import { getUserId } from "@/utils/functions";

const ProfilePasswordForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const {
        register,
        handleSubmit,
        reset,
        formState: { errors },
    } = useForm<TPasswordProfileData>({
        resolver: zodResolver(PASSWORD_PROFILE_SCHEMA),
    });
    const showToast = useToastMessage();
    const userToken = getUserId();

    const onSubmit: SubmitHandler<TPasswordProfileData> = async (data) => {
        const { repeatNewPassword, ...newPasswordData } = data;
        try {
            setIsLoading(true);
            const response = await axiosInstance.post(
                `${process.env.BASE_URL}/Users/SetNewPassword`,
                newPasswordData,
                {
                    headers: {
                        Authorization: `Bearer ${userToken}`,
                    },
                }
            );
            console.log(response);
            showToast("success", "Your password is successfully changed.");
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className={styles.profile_password}>
            <div className={styles.profile_password_header}>
                <h3>Change password</h3>
            </div>

            <div className={styles.profile_password_body}>
                <Form
                    onSubmit={handleSubmit(onSubmit)}
                    className={styles.profile_password_body_form}
                >
                    {PROFILE_PASSWORD_INPUTS.map((input) => (
                        <FormInput
                            input={input}
                            register={register}
                            errors={errors}
                            key={input.id}
                        />
                    ))}
                    <FormSubmit isLoading={isLoading} value="Change" />
                </Form>
            </div>
        </div>
    );
};

export default ProfilePasswordForm;
