import { Form, Button } from "react-bootstrap";
import styles from "./FormInput.module.scss";
import { useState } from "react";
import {
    ShowPasswordIcon,
    HidePasswordIcon,
    ErrorIcon,
} from "@/resources/icons";
import { checkInputType } from "@/utils/functions";
import Image from "next/image";
import { Tooltip, useDisclosure } from "@chakra-ui/react";

const FormInput = ({ input, errors, register }) => {
    const [showPassword, setShowPassword] = useState<boolean>(false);
    const { isOpen, onOpen, onToggle, onClose } = useDisclosure();

    const checkInputPassword =
        (errors.password && input.name === "password") ||
        (errors.repeatPassword && input.name === "repeatPassword") ||
        (errors.newPassword && input.name === "newPassword") ||
        (errors.repeatNewPassword && input.name === "repeatNewPassword");

    return input.type !== "select" ? (
        <Form.Group
            className="mb-3"
            controlId={"FormInput " + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <Form.Control
                    type={checkInputType(input, showPassword)}
                    placeholder={input.placeholder}
                    // @ts-ignore */
                    {...register(input.name)}
                    maxLength={50}
                />
                {input.type === "password" && (
                    <div
                        className={styles.input_toggle_button}
                        onClick={() => {
                            setShowPassword((prev) => !prev);
                        }}
                        style={{
                            right: checkInputPassword ? "55px" : "15px",
                        }}
                    >
                        {showPassword ? (
                            <Image
                                src={HidePasswordIcon}
                                width={20}
                                height={20}
                                alt="Hide password"
                            />
                        ) : (
                            <Image
                                src={ShowPasswordIcon}
                                width={20}
                                height={20}
                                alt="Show password"
                            />
                        )}
                    </div>
                )}
                <div className={styles.input_error}>
                    {errors[input.name] && (
                        <Tooltip
                            label={errors[input.name].message}
                            fontSize="sm"
                            bg="red.600"
                            textAlign="center"
                            isOpen={isOpen}
                        >
                            <Image
                                src={ErrorIcon}
                                width={20}
                                height={20}
                                alt="Error"
                                onMouseEnter={onOpen}
                                onMouseLeave={onClose}
                                onClick={onToggle}
                            />
                        </Tooltip>
                    )}
                </div>
            </div>
        </Form.Group>
    ) : (
        <Form.Group
            className="mb-3"
            controlId={"FormInput " + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <Form.Select multiple {...register(input.name)}>
                    <option>Company one</option>
                    <option>Company two</option>
                    <option>Company three</option>
                </Form.Select>
                <div
                    className={`${styles.input_error} ${styles.input_error_select}`}
                >
                    {errors[input.name] && (
                        <Tooltip
                            label={errors[input.name].message}
                            fontSize="sm"
                            bg="red.600"
                            textAlign="center"
                            isOpen={isOpen}
                        >
                            <Image
                                src={ErrorIcon}
                                width={20}
                                height={20}
                                alt="Error"
                                onMouseEnter={onOpen}
                                onMouseLeave={onClose}
                                onClick={onToggle}
                            />
                        </Tooltip>
                    )}
                </div>
            </div>
        </Form.Group>
    );
};

export default FormInput;
