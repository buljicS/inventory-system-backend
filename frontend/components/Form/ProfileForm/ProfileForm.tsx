import styles from "./ProfileForm.module.scss";
import { FormInput, FormSubmit } from "@/components";
import {
    PROFILE_FORM_INPUTS,
    PROFILE_INFORMATION_SCHEMA,
} from "@/utils/constants";
import { useForm, SubmitHandler } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { TProfileData } from "@/utils/types";
import { useState } from "react";
import { Form } from "react-bootstrap";

const ProfileForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm<TProfileData>({
        resolver: zodResolver(PROFILE_INFORMATION_SCHEMA),
    });

    const onSubmit = () => {};

    return (
        <div className={styles.profile_form}>
            <div className={styles.profile_form_header}>
                <h3>Profile information</h3>
            </div>
            <div className={styles.profile_form_body}>
                <Form
                    onSubmit={handleSubmit(onSubmit)}
                    className={styles.profile_form_body_form}
                >
                    {PROFILE_FORM_INPUTS.map((input) => (
                        <div key={input.id}>
                            <FormInput
                                input={input}
                                errors={errors}
                                register={register}
                            />
                        </div>
                    ))}
                    <FormSubmit isLoading={isLoading} value="Update" />
                </Form>
            </div>
        </div>
    );
};

export default ProfileForm;
