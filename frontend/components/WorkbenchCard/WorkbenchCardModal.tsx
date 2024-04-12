import { WorkbenchCard } from "@/components";
import styles from "./WorkbenchCardModal.module.scss";
import {
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalFooter,
    ModalBody,
    ModalCloseButton,
    useDisclosure,
    Button,
    FormControl,
    Select,
} from "@chakra-ui/react";

const WorkbenchCardModal = ({ icon, title, description, type }) => {
    const { isOpen, onOpen, onClose } = useDisclosure();

    return (
        <>
            <div className={styles.modal} onClick={onOpen}>
                <WorkbenchCard icon={icon} title={title} body={description} />
            </div>
            {type === "company" && (
                <Modal isOpen={isOpen} onClose={onClose}>
                    <ModalOverlay />
                    <ModalContent margin="5px">
                        <ModalHeader>Company</ModalHeader>
                        <ModalCloseButton />
                        <ModalBody>
                            <FormControl>
                                <Select placeholder="Select your company">
                                    <option>United Arab Emirates</option>
                                    <option>Nigeria</option>
                                </Select>
                            </FormControl>
                        </ModalBody>

                        <ModalFooter>
                            <Button variant="ghost" mr={3} onClick={onClose}>
                                Close
                            </Button>
                            <Button colorScheme="blue">Submit</Button>
                        </ModalFooter>
                    </ModalContent>
                </Modal>
            )}
        </>
    );
};

export default WorkbenchCardModal;
