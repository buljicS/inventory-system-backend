import { Form } from "react-bootstrap";
import styles from "./FormInput.module.scss";
import Image from "next/image";
import { Tooltip, useDisclosure } from "@chakra-ui/react";
import { ErrorIcon } from "@/resources/icons";

const FormSelectInput = ({ input, register, errors }) => {
    const { isOpen, onOpen, onToggle, onClose } = useDisclosure();
    return (
        <Form.Group
            className="mb-3"
            controlId={"FormInput " + input.id}
            key={input.id}
        >
            <Form.Label>{input.label}</Form.Label>
            <div className={styles.input}>
                <Form.Select
                    {...register(input.name)}
                    multiple={input.multiple ? true : false}
                >
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

export default FormSelectInput;
