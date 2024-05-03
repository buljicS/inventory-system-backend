import styles from "./ForgotPasswordForm.module.scss";
import {
    CHANGE_PASSWORD_INPUTS,
    CHANGE_PASSWORD_SCHEMA,
} from "@/utils/constants";
import { useState } from "react";
import { Form, Button } from "react-bootstrap";
import FormInput from "../FormInput/FormInput";
import { useForm, SubmitHandler } from "react-hook-form";
import { TForgotPasswordData } from "@/utils/types";
import axios from "axios";
import { zodResolver } from "@hookform/resolvers/zod";
import Spinner from "react-bootstrap/Spinner";
import { useSearchParams } from "next/navigation";
import { useRouter } from "next/navigation";
import { useToast } from "@chakra-ui/react";

const ForgotPasswordForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);

    const searchParams = useSearchParams();
    const router = useRouter();
    const toast = useToast();

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
            const response = await axios.post(
                `${process.env.BASE_URL}/Users/ResetPassword`,
                changePasswordData
            );

            switch (response.data.status) {
                case 200:
                    router.push("/login?status=2");
                    break;

                case 404:
                    toast({
                        title: "Status",
                        description: response.data.description,
                        status: "error",
                        duration: 3000,
                        isClosable: true,
                        position: "top-right",
                    });
                    setIsLoading(false);
                    break;
            }
        } catch (error) {
            console.log(error);
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

                    <Button type="submit">
                        {isLoading ? (
                            <Spinner animation="border" size="sm" />
                        ) : (
                            "Submit"
                        )}
                    </Button>
                </Form>
            </div>
        </div>
    );
};

export default ForgotPasswordForm;
