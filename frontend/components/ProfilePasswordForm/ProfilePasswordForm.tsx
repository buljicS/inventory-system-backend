import styles from "./ProfilePasswordForm.module.scss";
import {
    PROFILE_PASSWORD_INPUTS,
    PASSWORD_PROFILE_SCHEMA,
} from "@/utils/constants";
import { Form, Button } from "react-bootstrap";
import { FormInput } from "@/components";
import { useState } from "react";
import { useForm } from "react-hook-form";
import { TPasswordProfileData } from "@/utils/types";
import { zodResolver } from "@hookform/resolvers/zod";
import Spinner from "react-bootstrap/Spinner";

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

    const onSubmit = () => {};

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
                    <Button type="submit">
                        {isLoading ? (
                            <Spinner animation="border" size="sm" />
                        ) : (
                            "Change"
                        )}
                    </Button>
                </Form>
            </div>
        </div>
    );
};

export default ProfilePasswordForm;
