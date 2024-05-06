import "./RoomTable.scss";
import { DataTable, DataTableFilterMeta } from "primereact/datatable";
import { Column, ColumnEditorOptions } from "primereact/column";
import { useState } from "react";
import { TRooms } from "@/utils/types";
import { FilterMatchMode } from "primereact/api";
import RoomTableHeader from "./RoomTableHeader";
import { ROOM_TABLE_COLUMNS } from "@/utils/constants";
import data from "@/resources/MOCK_DATA.json";
import { Tag } from "primereact/tag";
import { Dropdown, DropdownChangeEvent } from "primereact/dropdown";
import "primereact/resources/themes/mira/theme.css";

import { InputText } from "primereact/inputtext";

const RoomTable = () => {
    const [rooms, setRooms] = useState<TRooms[]>(data);
    const [filters, setFilters] = useState<DataTableFilterMeta>({
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    });
    const [globalFilterValue, setGlobalFilterValue] = useState<string>("");
    const [selectedRooms, setSelectedRooms] = useState<TRooms[] | null>(null);
    const [statuses] = useState<string[]>(["Active", "Inactive"]);

    const onGlobalFilterChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        let _filters = { ...filters };

        // @ts-ignore
        _filters["global"].value = value;

        setFilters(_filters);
        setGlobalFilterValue(value);
    };

    const statusEditor = (options: ColumnEditorOptions) => {
        return (
            <Dropdown
                value={options.value}
                options={statuses}
                onChange={(e: DropdownChangeEvent) =>
                    options.editorCallback!(e.value)
                }
                placeholder="Select a Status"
                itemTemplate={(option) => {
                    return <Tag value={option}></Tag>;
                }}
            />
        );
    };

    const textEditor = (options) => {
        return (
            <InputText
                type="text"
                value={options.value}
                onChange={(e) => options.editorCallback(e.target.value)}
            />
        );
    };

    const onSubmit = (e) => {
        console.log(e);
    };

    return (
        <div className="card">
            <DataTable
                value={rooms}
                showGridlines
                filters={filters}
                header={
                    <RoomTableHeader
                        globalFilterValue={globalFilterValue}
                        onGlobalFilterChange={onGlobalFilterChange}
                        selectedRooms={selectedRooms}
                    />
                }
                emptyMessage="No rooms found."
                paginator
                rows={10}
                editMode="row"
                onRowEditComplete={onSubmit}
                selection={selectedRooms!}
                onSelectionChange={(e) => setSelectedRooms(e.value)}
                selectionMode="multiple"
            >
                <Column
                    selectionMode="multiple"
                    headerStyle={{ width: "3rem" }}
                ></Column>
                {ROOM_TABLE_COLUMNS.map((column) => (
                    <Column
                        field={column.field}
                        header={column.header}
                        editor={(options) => textEditor(options)}
                        sortable={column.sortable}
                        key={column.id}
                    />
                ))}
                <Column
                    field="roomInventoryActive"
                    header="Inventory status"
                    editor={(options) => statusEditor(options)}
                    body={(rowData) =>
                        rowData.roomInventoryActive ? "Active" : "Inactive"
                    }
                />
                <Column rowEditor></Column>
            </DataTable>
        </div>
    );
};

export default RoomTable;
