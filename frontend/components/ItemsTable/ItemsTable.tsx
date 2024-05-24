import "./ItemsTable.scss";
import "primereact/resources/themes/mira/theme.css";
import { useState } from "react";
import { DataTableFilterMeta, DataTable } from "primereact/datatable";
import { FilterMatchMode } from "primereact/api";
import { TItems } from "@/utils/types";
import { Column, ColumnEditorOptions } from "primereact/column";
import { ITEM_TABLE_COLUMNS } from "@/utils/constants";
import ItemTableHeader from "./ItemTableHeader";
import MOCK_DATA_ITEMS from "@/resources/MOCK_DATA_ITEMS.json";
import { InputText } from "primereact/inputtext";
import { classNames } from "primereact/utils";
import { QRCodeGenerateIcon } from "@/resources/icons";
import Image from "next/image";

const ItemsTable = () => {
    const [items, setItems] = useState<TItems[]>(MOCK_DATA_ITEMS);
    const [filters, setFilters] = useState<DataTableFilterMeta>({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const [globalFilterValue, setGlobalFilterValue] = useState<string>("");
    const [selectedItems, setSelectedItems] = useState<TItems[] | null>(null);

    const onGlobalFilterChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        let _filters = { ...filters };

        // @ts-ignore
        _filters["global"].value = value;

        setFilters(_filters);
        setGlobalFilterValue(value);
    };

    const onSubmit = (e) => {};

    const textEditor = (options) => {
        return (
            <InputText
                type="text"
                value={options.value}
                onChange={(e) => options.editorCallback(e.target.value)}
                validateOnly
            />
        );
    };

    const stockBodyTemplate = (rowData) => {
        const stockClassName = classNames("stock", {
            "stock-bg-red": rowData.itemQuantity === 0,
            "stock-bg-blue":
                rowData.itemQuantity > 0 && rowData.itemQuantity <= 10,
            "stock-bg-teal": rowData.itemQuantity > 10,
        });

        return <div className={stockClassName}>{rowData.itemQuantity}</div>;
    };

    const generateQRTemplate = () => {
        return (
            <div className="qr-code-template">
                <Image
                    src={QRCodeGenerateIcon}
                    width={24}
                    height={24}
                    alt="Qr code icon"
                />
            </div>
        );
    };

    return (
        <div className="card">
            <DataTable
                value={items}
                showGridlines
                filters={filters}
                header={
                    <ItemTableHeader
                        globalFilterValue={globalFilterValue}
                        onGlobalFilterChange={onGlobalFilterChange}
                        selectedItems={selectedItems}
                    />
                }
                emptyMessage="No items found."
                paginator
                rows={10}
                editMode="row"
                onRowEditComplete={onSubmit}
                selection={selectedItems!}
                onSelectionChange={(e) => setSelectedItems(e.value)}
                selectionMode="multiple"
            >
                <Column
                    selectionMode="multiple"
                    headerStyle={{ width: "3rem" }}
                ></Column>
                {ITEM_TABLE_COLUMNS.map((column) => (
                    <Column
                        field={column.field}
                        header={column.header}
                        editor={(options) => textEditor(options)}
                        sortable={column.sortable}
                        key={column.id}
                        body={column.id === 2 ? stockBodyTemplate : ""}
                    />
                ))}

                <Column body={generateQRTemplate}></Column>
                <Column rowEditor></Column>
            </DataTable>
        </div>
    );
};

export default ItemsTable;
