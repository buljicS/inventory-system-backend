import {
    Modal,
    ModalOverlay,
    ModalContent,
    ModalHeader,
    ModalBody,
    ModalCloseButton,
    Button,
    Tabs,
    TabList,
    TabPanels,
    Tab,
    TabPanel,
} from "@chakra-ui/react";
import { Form } from "react-bootstrap";

const AddItemModal = ({ addItemIsOpen, onAddItemClose }) => {
    return (
        <Modal isOpen={addItemIsOpen} onClose={onAddItemClose}>
            <ModalOverlay />
            <ModalContent>
                <ModalHeader> Add an item</ModalHeader>
                <ModalCloseButton />
                <ModalBody>
                    <Tabs>
                        <TabList>
                            <Tab>Add single item</Tab>
                            <Tab>Add multiple items</Tab>
                        </TabList>

                        <TabPanels>
                            <TabPanel>
                                <p>one!</p>
                            </TabPanel>
                            <TabPanel>
                                <p>two!</p>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </ModalBody>
            </ModalContent>
        </Modal>
    );
};

export default AddItemModal;
