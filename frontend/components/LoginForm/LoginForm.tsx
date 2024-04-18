import styles from "./LoginForm.module.scss";
import { useState, useEffect } from "react";
import { Form, Button } from "react-bootstrap";
import { LOGIN_SCHEMA, FORGOT_PASSWORD_SCHEMA } from "@/utils/constants";
import { zodResolver } from "@hookform/resolvers/zod";
import { LOGIN_INPUTS, FORGOT_PASSWORD_INPUT } from "@/utils/constants";
import { useForm, SubmitHandler } from "react-hook-form";
import {
    TLoginData,
    TForgotPassword,
    TForgotPasswordState,
    TJwtUser,
} from "@/utils/types";
import { useToast } from "@chakra-ui/react";
import { userAtom } from "@/utils/atoms";
import { useRecoilState } from "recoil";
import axios from "axios";
import { useRouter } from "next/navigation";
import { FormInput } from "@/components";
import Spinner from "react-bootstrap/Spinner";
import { useSearchParams } from "next/navigation";
import { userActionMessages } from "@/utils/functions";
import { jwtDecode } from "jwt-decode";

const LoginForm = () => {
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [user, setUser] = useRecoilState(userAtom);
    const [showForgotPassword, setShowForgotPassword] =
        useState<TForgotPasswordState>({
            button: false,
            form: false,
        });

    const toast = useToast();
    const router = useRouter();
    const searchParams = useSearchParams();
    const userActions = searchParams.get("status");

    useEffect(() => {
        userActionMessages(toast, userActions);
    }, []);

    const {
        register: loginRegister,
        handleSubmit: handleLoginSubmit,
        formState: { errors },
    } = useForm<TLoginData>({ resolver: zodResolver(LOGIN_SCHEMA) });

    const {
        register: forgotRegister,
        handleSubmit: handleForgotSubmit,
        formState: { errors: forgotErrors },
    } = useForm<TForgotPassword>({
        resolver: zodResolver(FORGOT_PASSWORD_SCHEMA),
    });

    const onLoginSubmit: SubmitHandler<TLoginData> = async (data) => {
        try {
            setIsLoading(true);
            const response = await axios.post(
                "http://www.insystem-api.localhost/api/Users/LoginUser",
                data
            );

            switch (response.data.status) {
                case "200":
                    setIsLoading(true);
                    const userInformation = jwtDecode(
                        response.data.token
                    ) as TJwtUser;
                    userInformation.jwt = response.data.token;
                    sessionStorage.setItem(
                        "user",
                        JSON.stringify(userInformation)
                    );
                    setUser((prev) => ({
                        ...prev,
                        approveLogin: true,
                    }));

                    router.push("/dashboard");
                    break;

                case "403":
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

                case "401":
                case "404":
                    toast({
                        title: "Status",
                        description: response.data.description,
                        status: "error",
                        duration: 3000,
                        isClosable: true,
                        position: "top-right",
                    });
                    setIsLoading(false);
                    setShowForgotPassword((prev) => ({
                        ...prev,
                        button: true,
                    }));
                    break;
            }
        } catch (error) {
            console.log(error);
            setIsLoading(false);
        }
    };

    const onforgotSubmit: SubmitHandler<TForgotPassword> = async (data) => {
        try {
            setIsLoading(true);
            const response = await axios.post(
                "http://www.insystem-api.localhost/api/Users/SendPasswordResetEmail",
                data
            );

            toast({
                title: "Status",
                description:
                    "If you have an account linked to this email, we have sent you instructions for resetting it in your inbox.",
                status: "success",
                duration: 6000,
                isClosable: true,
                position: "top-right",
            });
        } catch (error) {
            console.log(error);
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className={styles.form}>
            <div className={styles.form_header}>
                {showForgotPassword.form ? (
                    <>
                        <h1>Reset your password</h1>
                        <p>
                            If you have forgotten your password, don't worry!
                            You can change it by entering your email.
                        </p>
                    </>
                ) : (
                    <>
                        <h1>Login</h1>
                        <p>
                            Let's make every login count towards a seamlessly
                            organized inventory. Happy working!
                        </p>
                    </>
                )}
            </div>
            <Form
                onSubmit={
                    showForgotPassword.form
                        ? handleForgotSubmit(onforgotSubmit)
                        : handleLoginSubmit(onLoginSubmit)
                }
            >
                {showForgotPassword.form ? (
                    <FormInput
                        input={FORGOT_PASSWORD_INPUT}
                        errors={forgotErrors}
                        register={forgotRegister}
                    />
                ) : (
                    LOGIN_INPUTS.map((input) => (
                        <FormInput
                            key={input.id}
                            input={input}
                            errors={errors}
                            register={loginRegister}
                        />
                    ))
                )}

                <div className={styles.form_buttons}>
                    <Button type="submit">
                        {isLoading ? (
                            <Spinner animation="border" size="sm" />
                        ) : (
                            "Submit"
                        )}
                    </Button>
                    {showForgotPassword.button && (
                        <Button
                            onClick={() =>
                                setShowForgotPassword({
                                    button: false,
                                    form: true,
                                })
                            }
                        >
                            Forgot password?
                        </Button>
                    )}
                    {showForgotPassword.form && (
                        <Button
                            onClick={() =>
                                setShowForgotPassword({
                                    button: false,
                                    form: false,
                                })
                            }
                        >
                            Back to login
                        </Button>
                    )}
                </div>
            </Form>
        </div>
    );
};

export default LoginForm;
