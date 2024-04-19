import styles from "./ProfileForm.module.scss";
import { FormInput } from "@/components";
import {
    PROFILE_FORM_INPUTS,
    PROFILE_INFORMATION_SCHEMA,
} from "@/utils/constants";
import { useForm, SubmitHandler } from "react-hook-form";
import Spinner from "react-bootstrap/Spinner";
import { zodResolver } from "@hookform/resolvers/zod";
import { TProfileData } from "@/utils/types";
import { useState } from "react";
import { Form, Button } from "react-bootstrap";

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
        <Form onSubmit={handleSubmit(onSubmit)}>
            <div className={styles.form}>
                {PROFILE_FORM_INPUTS.map((input) => (
                    <div className={styles.form_input}>
                        <FormInput
                            key={input.id}
                            input={input}
                            errors={errors}
                            register={register}
                        />
                    </div>
                ))}
                <Button type="submit">
                    {isLoading ? (
                        <Spinner animation="border" size="sm" />
                    ) : (
                        "Update"
                    )}
                </Button>
            </div>
        </Form>
    );
};

export default ProfileForm;
