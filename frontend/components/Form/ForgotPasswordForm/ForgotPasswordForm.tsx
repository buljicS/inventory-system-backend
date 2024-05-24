import styles from "./ForgotPasswordForm.module.scss";
import {
    API_ENDPOINT,
    CHANGE_PASSWORD_INPUTS,
    CHANGE_PASSWORD_SCHEMA,
} from "@/utils/constants";
import { useState } from "react";
import { Form } from "react-bootstrap";
import { FormInput, FormSubmit } from "@/components";
import { useForm, SubmitHandler } from "react-hook-form";
import { TForgotPasswordData } from "@/utils/types";
import { zodResolver } from "@hookform/resolvers/zod";
import { useSearchParams } from "next/navigation";
import { useRouter } from "next/navigation";
import { useToastMessage } from "@/utils/hooks";
import axiosInstance from "@/utils/axiosInstance";

const ForgotPasswordForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const searchParams = useSearchParams();
    const router = useRouter();
    const showToast = useToastMessage();

    const {
        register: loginRegister,
        handleSubmit: handleSubmit,
        formState: { errors },
    } = useForm<TForgotPasswordData>({
        resolver: zodResolver(CHANGE_PASSWORD_SCHEMA),
    });

    const onChangePasswordSubmit: SubmitHandler<TForgotPasswordData> = async (
        data
    ) => {
        try {
            data.hash = searchParams.get("token");
            const { repeatNewPassword, ...changePasswordData } = data;
            setIsLoading(true);
            const response = await axiosInstance.post(
                `${process.env.BASE_URL}${API_ENDPOINT.FORGOT_PASSWORD}`,
                changePasswordData
            );
            switch (response.data.status) {
                case 200:
                    router.push("/login?status=2");
                    break;

                case 404:
                    showToast("error", response.data.description);
                    setIsLoading(false);
                    break;
                default:
                    setIsLoading(false);
            }
        } catch (error) {
            console.log(error);
            setIsLoading(false);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className={styles.forgot}>
            <div className={styles.forgot_header}>
                <h1>Change your password</h1>
            </div>
            <div className={styles.forgot_form}>
                <Form onSubmit={handleSubmit(onChangePasswordSubmit)}>
                    {CHANGE_PASSWORD_INPUTS.map((input) => (
                        <FormInput
                            key={input.id}
                            input={input}
                            errors={errors}
                            register={loginRegister}
                        />
                    ))}

                    <FormSubmit isLoading={isLoading} value="Submit" />
                </Form>
            </div>
        </div>
    );
};

export default ForgotPasswordForm;
